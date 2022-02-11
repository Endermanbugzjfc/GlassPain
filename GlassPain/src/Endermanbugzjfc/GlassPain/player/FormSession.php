<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\player;

use Endermanbugzjfc\GlassPain\GlassPain;
use Generator;
use SOFe\AwaitStd\Await;
use SOFe\InfoAPI\InfoAPI;
use Vecnavium\FormsUI\CustomForm;

class FormSession
{

    public function __construct(
        protected PlayerSession $playerSession
    )
    {
        $this->panelForm();
    }

    protected function panelForm() : Generator
    {
        $this->sendPanelForm(yield Await::RESOLVE);
        $data = yield from Await::ONCE;
    }

    protected function sendPanelForm(callable $callback) : void
    {
        $form = new CustomForm($callback);
        $config = GlassPain::getInstance()->config->PanelForm;
        $info = $this->getPlayerSession()->getInfo();
        $form->setTitle(InfoAPI::resolve(
            $config->Title,
            $info
        ));
        $animationDefault = $this->animationDefault
            ??= $this->getPlayerSession()->getAnimation();
        foreach (
            $this->getPlayerSession()->getAvailableAnimations()
            as $index => $animation
        ) {
            $animations[] = $animation->getConfig()->parseDisplayName();
            if ($animation === $animationDefault) {
                $animationDefaultIndex = $index;
            }
        }
        $form->addDropdown(InfoAPI::resolve(
            $config->AnimationDropdownLabel,
            $info
        ), $animations ?? [], $animationDefaultIndex ?? null, "AnimationDropdown");
        $form->addInput(InfoAPI::resolve(
            $config->AnimationSearchBarLabel,
            $info
        ), InfoAPI::resolve(
            $config->AnimationSearchBarPlaceholder,
            $info
        ), $animationDefault->getConfig()->DisplayName, "AnimationSearchBar");
        $this->getPlayerSession()->getPlayer()->sendForm($form);
    }

    /**
     * @return PlayerSession
     */
    public function getPlayerSession() : PlayerSession
    {
        return $this->playerSession;
    }

}