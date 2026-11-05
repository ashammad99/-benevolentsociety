<?php

namespace Tests\Feature\Public;

use App\Mail\ContactMessage;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    private function valid(array $overrides = []): array
    {
        return array_merge([
            'first_name' => "Sara",
            'last_name' => "O'Brien",
            'email' => 'sara@example.org',
            'phone' => '+970 59 000 0000',
            'reason' => 'volunteer',
            'message' => "I'd like to volunteer & help.",
        ], $overrides);
    }

    public function test_message_is_emailed_to_the_organisation_with_reply_to_the_sender(): void
    {
        Mail::fake();
        Setting::factory()->create(['key' => 'email', 'value' => 'office@example.org']);

        $this->post(route('contact.send'), $this->valid())
            ->assertRedirect(route('contact').'#contact-form')
            ->assertSessionHas('contact_sent');

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            $text = $mail->render();

            return $mail->hasTo('office@example.org')
                && $mail->hasReplyTo('sara@example.org')
                && str_contains($mail->envelope()->subject, 'Volunteering')
                && str_contains($text, "O'Brien")            // not &#039;
                && str_contains($text, "I'd like to volunteer & help.");
        });
    }

    public function test_falls_back_to_the_config_email_when_no_setting(): void
    {
        Mail::fake();

        $this->post(route('contact.send'), $this->valid())->assertSessionHas('contact_sent');

        Mail::assertSent(ContactMessage::class, fn ($mail) => $mail->hasTo(config('society.fallback.email')));
    }

    public function test_invalid_input_is_rejected_and_nothing_is_sent(): void
    {
        Mail::fake();

        $this->post(route('contact.send'), $this->valid([
            'email' => 'not-an-email',
            'reason' => 'donate',      // not one of the configured reasons
            'message' => '',
        ]))->assertSessionHasErrors(['email', 'reason', 'message']);

        Mail::assertNothingSent();
    }

    public function test_honeypot_submissions_are_silently_dropped(): void
    {
        Mail::fake();

        $this->post(route('contact.send'), $this->valid(['website' => 'http://spam.example']))
            ->assertSessionHas('contact_sent');

        Mail::assertNothingSent();
    }

    public function test_mail_failure_keeps_the_input_and_shows_an_error(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->post(route('contact.send'), $this->valid())
            ->assertSessionHas('contact_error')
            ->assertSessionHasInput('message', "I'd like to volunteer & help.");
    }

    public function test_sending_is_rate_limited(): void
    {
        Mail::fake();
        RateLimiter::clear('');

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('contact.send'), $this->valid())->assertRedirect();
        }

        $this->post(route('contact.send'), $this->valid())->assertStatus(429);
    }

    public function test_reason_list_and_messages_follow_the_language(): void
    {
        $this->get('/contact?lang=ar')->assertOk()
            ->assertSee('<option value="volunteer" >التطوع</option>', false)
            ->assertSee('اختر السبب…')
            ->assertDontSee('Inquire about services');

        $this->post(route('contact.send'), $this->valid(['first_name' => '']))
            ->assertSessionHasErrors(['first_name' => 'حقل الاسم الأول مطلوب.']);

        $this->get('/contact?lang=en')
            ->assertSee('>Volunteering</option>', false)
            ->assertDontSee('>التطوع</option>', false);
    }

    public function test_phone_is_optional_and_arabic_text_arrives_intact(): void
    {
        Mail::fake();
        $data = $this->valid(['first_name' => 'سارة', 'message' => 'أرغب بالتطوع والمساعدة']);
        unset($data['phone']);

        $this->post(route('contact.send'), $data)->assertSessionHas('contact_sent');

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            $text = $mail->render();

            return str_contains($text, 'سارة') && str_contains($text, 'أرغب بالتطوع والمساعدة')
                && str_contains($text, 'Phone / الهاتف: -');
        });
    }
}
