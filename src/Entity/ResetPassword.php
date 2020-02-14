<?php


namespace App\Entity;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;


class ResetPassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "Le mot de passe est invalide"
     * )
     */
    protected $oldPassword;

    protected $newPassword;

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword): void
    {
        $this->newPassword = $newPassword;
    }




}