<?php

namespace App\Service;

use App\Entity\WebHook;
use Conduction\CommonGroundBundle\Service\CommonGroundService;
use DateInterval;
use DateTime;
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
        if($webHook->getRequest()){

        } elseif($webHook->getAssent()) {
            $this->processAssent($webHook->getAssent());
        }
        $this->em->persist($webHook);
        $this->em->flush();
    }

    public function createMessage($content, $resource, $receiver, $sender, $organization){
        $messages = [];
        $message['service'] = $this->commonGroundService->getResourceList(['component'=>'bs', 'type'=>'services'], "?type=mailer&organization={$organization}")['hydra:member'][0]['@id'];
        $message['status'] = 'queued';
        $message['sender'] = $sender;
        $message['reciever'] = $receiver;
        $message['content'] = $content;
        $message['data'] = ['resource'=>$resource, 'contact'=>$message['reciever'], 'organization'=>$message['sender']];
        return $message;
    }

    public function processAssent(string $assent){
        $assent = $this->commonGroundService->getResource($assent);
        $contact = $assent['contact'];
        $content = $this->commonGroundService->getResource(['component'=>'wrc', 'type'=>'applications', 'id'=>"{$this->params->get('app_id')}/e-mail-instemming"])['@id'];
        $senderArray = $this->commonGroundService->isCommonGround($assent['requester']);

        $sender = $contact;
        $organization = $this->commonGroundService->getResource(['component'=>'wrc', 'type'=>'applications', 'id'=>"{$this->params->get('app_id')}"]);

        if($senderArray['component'] == 'wrc'){
            $organization = $this->commonGroundService->getResource($senderArray);
            if(key_exists('contact', $organization) && $organization['contact']){
                $sender = $organization['contact'];
            }
            $organization = $organization['@id'];
            var_dump($organization);
        } elseif($senderArray['component'] == 'cc'){
            $cc = $this->commonGroundService->getResource($senderArray);
            if(key_exists('emails', $cc) && count($cc['emails']) > 0 && key_exists('email', $cc['emails'][0])){
                $sender = $assent['requester'];
            }
        }

        $message = $this->createMessage($content, $assent, $contact, $sender, $organization);
        $result[] = $this->commonGroundService->createResource($message, ['component'=>'bs', 'type'=>'messages'])['@id'];

        return $result;
    }

    public function processRequest(string $request, string $changelog){
        $request = $this->commonGroundService->getResource($request);
        $changeLogs = $this->commonGroundService->getResource($request.'/change_log');
    }
}
