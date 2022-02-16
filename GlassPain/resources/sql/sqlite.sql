-- #!sqlite
-- #{ init
-- #    { player_animation_config
CREATE TABLE IF NOT EXISTS glass_pain_player_animation_config
(
    uuid        BINARY(16)   NOT NULL,
    animation   VARCHAR(255) NOT NULL,
    config      TEXT            DEFAULT NULL,
    last_enable BIGINT UNSIGNED DEFAULT NULL, -- time()

    PRIMARY KEY (uuid, animation)
);
-- #    }
-- #}
-- #{ player
-- #    { animation
-- #        { get_all
-- #          :uuid string
SELECT animation,
       config
FROM glass_pain_player_animation_config
WHERE uuid = :uuid
ORDER BY last_enable DESC
-- #        {
-- #    }
-- #}
-- #{ animation
-- #    { get_users_count
-- #      :animation string
SELECT count(uuid)
FROM glass_pain_player_animation_config
WHERE animation = :animation;
-- #    {
-- #}
