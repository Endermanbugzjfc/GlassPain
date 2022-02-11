<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\player;

use Endermanbugzjfc\GlassPain\GlassPain;
use Generator;
use SOFe\AwaitStd\Await;
use SOFe\InfoAPI\InfoAPI;
use Vecnavium\FormsUI\CustomForm;
use function explode;
use function str_contains;
use function trim;

class FormSession
{

    public function __construct(
        protected PlayerSession $playerSession
    )
    {
        $this->panelForm();
    }

    protected ?string $search = null;

    protected function panelForm() : Generator
    {
        while (true) {
            $this->sendPanelForm(yield Await::RESOLVE);
            $data = yield from Await::ONCE;
            $animationName = $data["AnimationDropdown"];
            if ($data["AnimationSearchBar"] !== $animationName) {
                $this->search = $animationName;
                continue;
            }
        }
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
            $animationName = $animation->getConfig()->parseDisplayName();
            if ($this->animationShouldDisplayInDropdown($animationName)) {
                $animations[] = $animationName;
            }
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
        $this->resetParameters();
    }

    protected function animationShouldDisplayInDropdown(
        string $animationName
    ) : bool
    {
        if ($this->search === null) {
            return true;
        }
        $keywords = explode(" ", trim($this->search));
        foreach ($keywords as $keyword) {
            if (str_contains($animationName, $keyword)) {
                return true;
            }
        }
        return false;
    }

    public function resetParameters() : void
    {
        $this->search = null;
    }

    /**
     * @return PlayerSession
     */
    public function getPlayerSession() : PlayerSession
    {
        return $this->playerSession;
    }

}