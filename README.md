<!-- btw this is directly based on multiworld's readme thats why they look alike lol -->
# 🪟 Glass Pain
💡 Hint messages
🔐 Permission node for customisation
🌐 Multi-language system
🔌 API for anticheats

<p align="center">
  <img alt="Demonstration GIF" src="https://github.com/Endermanbugzjfc/GlassPain/assets/53002741/7f0525f1-c473-4a96-b1ea-15ed0dd176bf"><br>
  <a href="https://poggit.pmmp.io/p/GlassPain">  
    <img alt="Poggit release (latest)" src="https://poggit.pmmp.io/shield.downloads/GlassPain?style=for-the-badge">  
  </a>
  <a href="https://github.com/Endermanbugzjfc/GlassPain">  
    <img alt="Stars on GitHub" src="https://img.shields.io/github/stars/Endermanbugzjfc/GlassPain?style=for-the-badge">
  </a>
</p>

Getting bent out of shape for glass pane block's small hitbox?
This plugin turns glass panes into their full-block versions when one is holding a block to help align with them more easily!

If the players are too close to some glass panes that are supposed to be replaced,
a warning message will show on their screen so they will be less likely to get stuck or just fall off:
<p align="center">
  <img alt="Demonstration GIF of the warning message" src="https://github.com/Endermanbugzjfc/GlassPain/assets/53002741/4f811744-3df4-43b9-8aa4-bb1fcbe7ca26"><br>
</p>

# 🔐 Permission node
- `glasspain.use`: One can enjoy the feature when having this perm attached to.

*Want to enable this feature for certain groups of people? Use [RankSystem](https://poggit.pmmp.io/p/RankSystem/) achieve it!*

# 🌐 Existing languages
- English
- Chinese Traditional & Simplified
- Vietnam *[@NhanAZ](https://github.com/NhanAZ)*

# 🔌 API
In spite of being a big spaghetti, this plugin still provides an API.
If you are developing an anticheat, please consider taking a look because this plugin sends fake blocks to clients:
```php
use Endermanbugzjfc\GlassPain\API as GlassPain;

$block = GlassPain::getInstance()->getClientSideBlock($player, $pos);
...
```
