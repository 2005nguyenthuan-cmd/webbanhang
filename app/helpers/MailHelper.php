<?php
require_once 'app/config/mail.php';
require_once 'app/helpers/phpmailer/Exception.php';
require_once 'app/helpers/phpmailer/PHPMailer.php';
require_once 'app/helpers/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper {
    public static function sendEmail($toEmail, $toName, $subject, $bodyHTML) {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;
            $mail->CharSet    = 'UTF-8';

            // Recipients
            $mail->setFrom(MAIL_USERNAME, MAIL_FROM_NAME);
            $mail->addAddress($toEmail, $toName);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $bodyHTML;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Lỗi gửi email: {$mail->ErrorInfo}");
            return false;
        }
    }

    public static function sendVerificationEmail($toEmail, $fullName, $verifyLink) {
        $subject = 'Xác thực tài khoản TechStore';
        $bodyHTML = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
            <h2 style='color: #4F46E5; text-align: center;'>Chào mừng bạn đến với TechStore!</h2>
            <p>Xin chào <strong>{$fullName}</strong>,</p>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại hệ thống của chúng tôi. Để hoàn tất việc đăng ký và bảo mật tài khoản, vui lòng xác thực địa chỉ email của bạn bằng cách nhấp vào nút dưới đây:</p>
            <div style='text-align: center; margin: 30px 0;'>
                <a href='{$verifyLink}' style='background-color: #4F46E5; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;'>Xác thực Email</a>
            </div>
            <p>Hoặc bạn có thể copy và dán đường link sau vào trình duyệt:</p>
            <p style='background-color: #f3f4f6; padding: 10px; border-radius: 4px; word-break: break-all; font-size: 14px;'>{$verifyLink}</p>
            <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
            <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
            <p style='font-size: 12px; color: #888; text-align: center;'>&copy; 2026 TechStore. All rights reserved.</p>
        </div>";

        return self::sendEmail($toEmail, $fullName, $subject, $bodyHTML);
    }

    public static function sendResetPasswordEmail($toEmail, $fullName, $resetLink) {
        $subject = 'Khôi phục mật khẩu TechStore';
        $bodyHTML = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
            <h2 style='color: #F59E0B; text-align: center;'>Khôi phục mật khẩu</h2>
            <p>Xin chào <strong>{$fullName}</strong>,</p>
            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn tại TechStore. Để đặt lại mật khẩu, vui lòng nhấp vào nút dưới đây:</p>
            <div style='text-align: center; margin: 30px 0;'>
                <a href='{$resetLink}' style='background-color: #F59E0B; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 16px;'>Đặt lại mật khẩu</a>
            </div>
            <p>Hoặc bạn có thể copy và dán đường link sau vào trình duyệt:</p>
            <p style='background-color: #f3f4f6; padding: 10px; border-radius: 4px; word-break: break-all; font-size: 14px;'>{$resetLink}</p>
            <p style='color: #ef4444; font-size: 13px;'>Lưu ý: Link khôi phục này chỉ có hiệu lực trong một thời gian ngắn. Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng đổi mật khẩu ngay lập tức hoặc liên hệ bộ phận hỗ trợ để được trợ giúp.</p>
            <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
            <p style='font-size: 12px; color: #888; text-align: center;'>&copy; 2026 TechStore. All rights reserved.</p>
        </div>";

        return self::sendEmail($toEmail, $fullName, $subject, $bodyHTML);
    }
}
