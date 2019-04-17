<?php
namespace jx\admin\models\form;

use Yii;
use jx\admin\models\Master;
use yii\base\Model;

/**
 * Password reset request form
 * @author Au zn <690550322@qq.com>
 * @since Full version
 */
class PasswordResetRequest extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => 'jx\admin\models\Master',
                'filter' => ['status' => Master::STATUS_ACTIVE],
                'message' => 'There is no master with such email.'
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user Master */
        $user = Master::findOne([
            'status' => Master::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if ($user) {
            if (!Master::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }

            if ($user->save()) {
                return Yii::$app->mailer->compose(['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'], ['master' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                    ->setTo($this->email)
                    ->setSubject('Password reset for ' . Yii::$app->name)
                    ->send();
            }
        }

        return false;
    }
}
