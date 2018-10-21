<html>
<br/>

لاستعادة كلمة المرور اضغط على الرابط التالى: <a href="{{ $link = Request()->root().'/password/reset/'. $token.'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
</html>