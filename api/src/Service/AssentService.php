<?php

namespace App\Service;

use App\Entity\WebHook;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AssentService
{
    private $em;
    private $commonGroundService;
    private $params;

    public function __construct(EntityManagerInterface $em, CommonGroundService $commonGroundService, ParameterBagInterface $params)
    {
        $this->em = $em;
        $this->commonGroundService = $commonGroundService;
        $this->params = $params;
    }

    public function webHook(WebHook $webHook)
    {
        if ($webHook->getRequest()) {
        } elseif ($webHook->getAssent()) {
            $webHook->setResult($this->processAssent($webHook->getAssent()));
        }
        $this->em->persist($webHook);
        $this->em->flush();
    }

    public function createMessage($mail)
    {
        $messages = [];
        $message['service'] = $this->commonGroundService->getResourceList(['component'=>'bs', 'type'=>'services'], "?type=mailer&organization={$mail['serviceOrganization']}")['hydra:member'][0]['@id'];
        $message['status'] = 'queued';
        $message['sender'] = $mail['sender'];
        $message['reciever'] = $mail['receiver'];
        $message['content'] = $mail['content'];
        $message['data'] = ['resource'=>$mail['resource'], 'receiver'=>$mail['receiver'], 'organization'=>$mail['organization'], 'sender'=>$mail['sender']];

        return $message;
    }

    public function processAssent(string $assent)
    {
        $mail = [];
        $mail['assent'] = $this->commonGroundService->getResource($assent);
        $mail['contact'] = $mail['assent']['contact'];
        $mail['content'] = $this->commonGroundService->getResource(['component'=>'wrc', 'type'=>'applications', 'id'=>"{$this->params->get('app_id')}/e-mail-instemming"])['@id'];
        $mail['serviceOrganization'] = $this->commonGroundService->getResource(['component'=>'wrc', 'type'=>'applications', 'id'=>"{$this->params->get('app_id')}"])['organization']['@id'];
        $senderArray = $this->commonGroundService->isCommonGround($mail['assent']['requester']);

        $mail['contact'] = $mail['assent']['contact'];
        $mail['organization'] = $this->commonGroundService->getResource(['component'=>'wrc', 'type'=>'applications', 'id'=>"{$this->params->get('app_id')}"]);

        if ($senderArray['component'] == 'wrc') {
            $organization = $this->commonGroundService->getResource($senderArray);
            if (key_exists('contact', $organization) && $organization['contact']) {
                $sender = $organization['contact'];
            }

        } elseif ($senderArray['component'] == 'cc') {
            $cc = $this->commonGroundService->getResource($senderArray);
            if (key_exists('emails', $cc) && count($cc['emails']) > 0 && key_exists('email', $cc['emails'][0])) {
                $sender = $mail['assent']['requester'];
            }
        }

        $message = $this->createMessage($mail);
        $result[] = $this->commonGroundService->createResource($message, ['component'=>'bs', 'type'=>'messages'])['@id'];

        return $result;
    }

    public function processRequest(string $request, string $changelog)
    {
        $request = $this->commonGroundService->getResource($request);
        $changeLogs = $this->commonGroundService->getResource($request.'/change_log');
    }
}
