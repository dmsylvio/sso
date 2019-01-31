<?php

namespace app\vendor\dmsylvio\sso\behaviors;

use Yii;

class AccessRule extends \yii\filters\AccessRule{

    /**
     * @param \yii\web\User $user
     * @return bool
     * @throws \yii\db\Exception
     */
    protected function matchRole($user){

        // verifica se o sistema existe
        // o sistema tem que estar cadastrado em config/params.php
        if(!is_null(AccessRule::getSystem())){

            // carrega da controller as roles necessárias para acessar cada action do determinado controller

            foreach ($this->roles as $role){

                // verifica se o usuário está logado no sistema
                if (!$user->getIsGuest()){

                    // verifica se o usuário tem perfil maior ou igual ao necessário para acessar a action
                    if(AccessRule::getUserPerfil() >= $role){

                        // unica condição que retorna verdadeiro
                        // concede acesso em tempo real ao usuário
                        return true;

                    }else{

                        // retorna false
                        // nega acesso ao usuário devido a seu perfil ser inferior ao necessário para acessar
                        return false;
                    }

                }else{

                    // caso usuário não esteja logado
                    // retorna false e nega acesso
                    return false;
                }

                // caso não exista nenhuma regra ou role cadastrada para determinada action de um determinado controller
                // retorna falso e nega acesso
                return false;

            }

            // retorna falso e nega acesso
            // por motivos de força maior ele nao entrou no foreach devido a isso deve se negar o acesso.
            return false;

        }else{

            // retorna falso e nega acesso
            return false;

        }
    }

    /**
     * @return int|null
     * @throws \yii\db\Exception
     */
    public function getSystem(){

        $systemid = (int)Yii::$app->params['systemId'];

        if (!is_null($systemid)){

            $request = Yii::$app->db->createCommand("SELECT id FROM sistema WHERE id = '".$systemid."'")->queryAll();

            if (!empty($request)){

                return (int)$request[0]['id'];

            }else{

                return (int)0;
            }

        }

        return null;
    }

    /**
     * @param $controllername
     * @return array|null
     * @throws \yii\db\Exception
     */
    public function getActions($controllername){

        $systemid = AccessRule::getSystem();

        if (!is_null($systemid)){

            $actions = Yii::$app->db->createCommand("SELECT action FROM actions WHERE controller = '".$controllername."' AND id_sistema = '".$systemid."'")
                                    ->queryAll();

            if(!empty($actions)){

                foreach($actions as $action){
                    $array[] = $action['action'];
                }

            }else{

                $array = [
                    '0' => 'index'
                ];
            }

            return $array;

        }

        return null;
    }

    /**
     * @param $controllername
     * @return array|null
     * @throws \yii\db\Exception
     */
    public function getRoles($controllername){

        $systemid = AccessRule::getSystem();

        if (!is_null($systemid)) {

            $request = Yii::$app->db->createCommand("SELECT * FROM sps_perfis_controller('" . $controllername . "', " . $systemid . ")")->queryAll();

            if (!empty($request)) {

                foreach($request as $r){
                    $actions = $r['action'];
                    $perfil = $r['perfil'];
                    $roles = $r['role'];

                    $role[] = [
                        'actions' => [$actions],
                        'allow' => true,
                        'roles' => [$roles],
                    ];

                }

            }else{

                $role[] = [
                    'actions' => [
                        '0' => 'index'
                    ],
                    'allow' => true,
                    'roles' => [
                        '0' => 100
                    ],
                ];
            }

            return $role;

        }

        return null;
    }

    /**
     * @return int|null
     */
    public function getUserId(){

        if (!Yii::$app->user->isGuest) {

            return (int)Yii::$app->user->identity->id;
        }

        return null;
    }

    /**
     * @return int|null
     * @throws \yii\db\Exception
     */
    public function getUserPerfil(){

        $userid = AccessRule::getUserId();

        $systemid = AccessRule::getSystem();

        if ($userid != null || $systemid != null || $userid && $systemid != null){

            $request =  Yii::$app->db->createCommand("SELECT role_perfil, role FROM sps_perfil('".$userid."', '".$systemid."')")->queryAll();

            if (!empty($request)){

                foreach($request as $r){
                    // $profile = $r['role_perfil']; Return profile name Ex: Administrador
                    $role = (int)$r['role']; // return Role Ex: 40
                }

            }else{

                $role = (int)0;
            }
            return $role;
        }

        return null;

    }
}