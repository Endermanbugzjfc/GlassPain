# GlassPain
<!-- btw this is directly based on multiworld's readme thats why they look alike lol -->
# 🪟 Glass Pain
💡 Hint messages
🔐 Permission node for customisation
🌐 Multi-language system
🔌 API for anticheats

<p align="center">
  <img height=480 alt="Demonstration GIF" src=""><br>
  <a href="https://poggit.pmmp.io/p/GlassPain">  
    <img alt="Poggit release (latest)" src="https://poggit.pmmp.io/shield.downloads/GlassPain?style=for-the-badge">  
  </a>
  <a href="https://github.com/Endermanbugzjfc/GlassPain">  
    <img alt="Stars on GitHub" src="https://img.shields.io/github/stars/Endermanbugzjfc/GlassPain?style=for-the-badge">
  </a>
</p>

Getting bent out of shape for glass pane block's small hitbox?
This plugin turns glass panes into their full-block versions when one is holding any block!

A warning message will be sent as well if one is too close to glass panes that are supposed to be replaced so one would not stuck in the block or just fall off:
<p align="center">
  <img height=480 alt="Demonstration GIF of the warning message" src=""><br>
</p>

# 🔐 Permission node
- `glasspain.use`: One can enjoy the feature when have this perm attached to.

*Want to enable this feature for certain groups of people? Use [RankSystem](https://poggit.pmmp.io/p/RankSystem/) achieve it!*

# 🌐 Existing languages
- English
- Chinese Traditional & Simplified
- Vietnam @NhanAZ

# 🔌 API
Inspite of being a big spaghetti, this plugin still provides an API.
If you are developing an anticheat, please consider taking a look because this plugin sends fake blocks to clients:
```php
use Endermanbugzjfc\GlassPain\API as GlassPain;

$block = GlassPain::getInstance()->getClientSideBlock($player, $pos);
...
```