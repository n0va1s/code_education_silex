<?php

namespace Api\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    private $passwordEncoder;
    private $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function createAdminUser($username, $password)
    {
        $user = new \Api\User\UserEntity($this->em);
        $user->username = $username;
        $user->plainPassword = $password;
        $user->roles = 'ROLE_ADMIN';
        $this->encodePassword($user);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }
/*
    public function insert($user)
    {
        $this->encodePassword($user);
        $this->em->persist($user);
        $this->em->flush();
    }
*/
    public function setPasswordEncoder(PasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function objectToArray(User $user)
    {
        return array(
            'id' => $user->id,
            'username' => $user->username,
            'password' => $user->password,
            'roles' => implode(',', $user->roles),
            'created_at' => $user->createdAt->format(self::DATE_FORMAT),
        );
    }

    public function arrayToObject($userArr, $user = null)
    {
        if (!$user) {
            $user = new \Api\User\UserEntity($this->em);

            $user->id = isset($userArr['id']) ? $userArr['id'] : null;
        }

        $username = isset($userArr['username']) ? $userArr['username'] : null;
        $password = isset($userArr['password']) ? $userArr['password'] : null;
        $roles = isset($userArr['roles']) ? explode(',', $userArr['roles']) : array();
        $createdAt = isset($userArr['created_at']) ? \DateTime::createFromFormat(self::DATE_FORMAT, $userArr['created_at']) : null;

        if ($username) {
            $user->username = $username;
        }

        if ($password) {
            $user->password = $password;
        }

        if ($roles) {
            $user->roles = $roles;
        }

        if ($createdAt) {
            $user->createdAt = $createdAt;
        }

        return $user;
    }

    public function loadUserByUsername($username)
    {

        //$user = $this->findOneByUsername($username);
        $query = $this->em->createQuery('select u from \Api\User\UserEntity u where u.username = :username')
                          ->setParameter('username', $username);
              
        $user = $query->getOneOrNullResult();
          
        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Usuário "%s" não encontrado. Verifique se vc digitou corretamente.', $username));
        }

        return $this->arrayToObject($user->toArray());
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Esse "%s" não é um usuário válido.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Api\User\UserEntity';
    }

    private function encodePassword(\Api\User\UserEntity $user)
    {
        if ($user->plainPassword) {
            $user->password = $this->passwordEncoder->encodePassword($user->plainPassword, $user->getSalt());
        }
    }
}
