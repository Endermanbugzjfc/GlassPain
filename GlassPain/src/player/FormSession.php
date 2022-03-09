<?php

declare(strict_types=1);

namespace Endermanbugzjfc\GlassPain\player;

use Endermanbugzjfc\GlassPain\animation\AnimationBase;
use Endermanbugzjfc\GlassPain\GlassPain;
use Generator;
use SOFe\AwaitStd\Await;
use SOFe\InfoAPI\InfoAPI;
use Vecnavium\FormsUI\CustomForm;
use function count;
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

    /**
     * @var AnimationBase[]
     */
    protected array $animations;

    protected ?AnimationBase $animation = null;

    protected function panelForm() : Generator
    {
        while (true) {
            $this->sendPanelForm(yield Await::RESOLVE);
            $data = yield from Await::ONCE;
            if ($data === null) {
                break;
            }
            $animationDropdown = $data["AnimationDropdown"];
            $animation = $this->animations[$animationDropdown];
            if ($this->animation !== $animation) {
                if ($this->search === null) {
                    if ($animationDropdown === 0) {
                        $animationName = $animation->getConfig()->parseDisplayName();
                        if ($data["AnimationSearchBar"] !== $animationName) {
                            $this->search = $animationName;
                            continue;
                        }
                    }
                }
                $this->animation = $animation;
                $this->search = null;
                continue;
            }

            $toggle = $data["Toggle"];
            if ($this->getPlayerSession()->getAnimation() === $animation) {
                if (!$toggle) {
                    $this->getPlayerSession()->setAnimation(null);
                }
            } else {
                if ($toggle) {
                    $this->getPlayerSession()->setAnimation($animation);
                }
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
        $animationDefault = $this->animation
            ?? $this->getPlayerSession()->getAnimation();
        foreach (
            $this->getPlayerSession()->getAvailableAnimations()
            as $index => $animation
        ) {
            $animationName = $animation->getConfig()->parseDisplayName();
            if ($this->animationShouldDisplayInDropdown($animationName)) {
                $this->animations[] = $animation;
                $animationNames[] = $animationName;
            }
            if ($animation === $animationDefault) {
                $animationDefaultIndex = $index;
            }
        }
        $form->addDropdown(InfoAPI::resolve(
            $config->AnimationDropdownLabel,
            $info
        ), $animationNames ?? [], $animationDefaultIndex ?? null, "AnimationDropdown");
        $form->addInput(InfoAPI::resolve(
            $config->AnimationSearchBarLabel,
            $info
        ), InfoAPI::resolve(
            $config->AnimationSearchBarPlaceholder,
            $info
        ), $animationDefault->getConfig()->DisplayName, "AnimationSearchBar");

        if (count($this->animations ?? []) === 1) {
            $this->animation = isset($animationDefaultIndex)
                ? $animationDefault
                : $this->animations[0] ?? null;
        }

        if ($this->animation !== null) {
            $enabled = $this
                    ->animation === $this
                    ->getPlayerSession()
                    ->getAnimation();
            $form->addToggle(InfoAPI::resolve(
                $enabled
                    ? $config->ToggleLabelEnabled
                    : $config->ToggleLabelDisabled,
                $info
            ), $enabled, "Toggle");
        }

        $this->getPlayerSession()->getPlayer()->sendForm($form);
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

    /**
     * @return PlayerSession
     */
    public function getPlayerSession() : PlayerSession
    {
        return $this->playerSession;
    }

}