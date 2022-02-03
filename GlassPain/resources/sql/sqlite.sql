-- #!sqlite
-- #{ init
-- #    { player
CREATE TABLE IF NOT EXISTS glass_pain_player
(
    uuid      BINARY(16) NOT NULL,
    animation VARCHAR(255) DEFAULT NULL,

    PRIMARY KEY (uuid)
);
-- #    }
-- #}