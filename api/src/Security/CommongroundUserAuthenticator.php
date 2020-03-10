<?php

// src/Security/TokenAuthenticator.php
namespace App\Security;

use App\Security\User\CommongroundUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface; 
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CommongroundUserAuthenticator extends AbstractGuardAuthenticator
{
	private $em;
	private $params;
	
	public function __construct(EntityManagerInterface $em, ParameterBagInterface $params)
	{
		$this->em = $em;
		$this->params = $params;
	}
	
	/**
	 * Called on every request to decide if this authenticator should be
	 * used for the request. Returning false will cause this authenticator
	 * to be skipped.
	 */
	public function supports(Request $request)
	{
		return $request->headers->has('X-AUTH-TOKEN');
	}
	
	/**
	 * Called on every request. Return whatever credentials you want to
	 * be passed to getUser() as $credentials.
	 */
	public function getCredentials(Request $request)
	{
		return [
				'token' => $request->headers->get('X-AUTH-TOKEN'),
		];
	}
	
	public function getUser($credentials, UserProviderInterface $userProvider)
	{
		/*
		$token = new CsrfToken('authenticate', $credentials['csrf_token']);
		if (!$this->csrfTokenManager->isTokenValid($token)) {
			throw new InvalidCsrfTokenException();
		}		
		*/ 
		
		$users = $this->commonGroundService->getResourceList($this->params->get('auth_provider_user').'/users',["username"=> $credentials['username']], true);
		$users = $users["hydra:member"];
				
		if(!$users ||count($users) < 1){
			return;			
		}
		
		$user = $users[0];
				
		return new CommongroundUser($user['username'], $user['id'], null, ['ROLE_USER'],$user['person'],$user['organization']);
	}
	
	public function checkCredentials($credentials, UserInterface $user)
	{
		
		$user = $this->commonGroundService->createResource($credentials, $this->params->get('auth_provider_user').'/login');
		
		// return true to cause authentication success
		return true;
	}
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
	{		
		return new RedirectResponse($this->urlGenerator->generate('app_user_dashboard'));
	}
	
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		var_dump('noooz');
		
		return new JsonResponse($data, Response::HTTP_FORBIDDEN);
	}
	
	/**
	 * Called when authentication is needed, but it's not sent
	 */
	public function start(Request $request, AuthenticationException $authException = null)
	{
		return new RedirectResponse($this->urlGenerator->generate('app_user_login'));
	}
	
	public function supportsRememberMe()
	{
		return false;
	}
}