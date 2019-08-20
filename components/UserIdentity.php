<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $_id;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $criteria = new CDbCriteria;
        $criteria->condition = "ID = :username";
        $criteria->params = array(
            ':username' => $this->username
        );
        $user = UserMaster::model()->find($criteria);
        if (!empty($user)) {
            if ($user->Password !== md5($this->password)) {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            } else {
                $this->errorCode = self::ERROR_NONE;
                $this->_id = $user->ID;
                $this->setState('userId', $this->_id);
                $this->setState('userName', $user->UserName);
                $this->setState('role', $user->Role);
                $this->setState('module', $user->Module);
                $this->setState('homeUrl', array('site/index'));

                Yii::app()->session['loginSession'] = array(
                    'userId' => $this->_id,
                    'userName' => $this->getState('userName'),
                    'role' => $this->getState('role'),
                    'module' => $this->getState('module'),
                    'password' => $user->Password
                );
                return !$this->errorCode;
            }
        } else {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
    }

}
