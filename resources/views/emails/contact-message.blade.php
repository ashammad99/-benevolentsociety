{{-- Plain-text email: {!! !!} keeps characters such as ' and & as typed; nothing here is rendered as HTML. --}}
New message from the website contact form.
رسالة جديدة من نموذج التواصل في الموقع.

Name / الاسم: {!! $data['first_name'] !!} {!! $data['last_name'] !!}
Email / البريد: {!! $data['email'] !!}
Phone / الهاتف: {!! $data['phone'] ?: '-' !!}
Reason / السبب: {!! $reasonLabel !!}

Message / الرسالة:
{!! $data['message'] !!}

--
Reply to this email to answer the sender directly.
للرد على المرسل مباشرةً، قم بالرد على هذه الرسالة.
