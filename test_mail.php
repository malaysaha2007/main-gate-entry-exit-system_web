<?php
require 'config/mail.php';

$result = sendMail(
    'malaymanpur07@gmail.com',
    'Mail Test Successful',
    '<h3>Hello!</h3><p>Your email system is working correctly.</p>'
);

echo $result ? 'Mail sent successfully' : 'Mail failed';
