<?php
/**
 * Created by PhpStorm.
 * User: Usamo
 * Date: 28.03.2018
 * Time: 11:45
 */

namespace UserBundle\Controller;


class SecurityController extends \FOS\UserBundle\Controller\SecurityController
{
    protected function renderLogin(array $data)
    {
        return $this->render('Security/login.html.twig', $data);
//        return parent::renderLogin($data);
    }
}