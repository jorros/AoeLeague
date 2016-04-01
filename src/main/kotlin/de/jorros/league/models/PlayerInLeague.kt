package de.jorros.league.models

import org.springframework.data.mongodb.core.mapping.DBRef

data class PlayerInLeague(
        @DBRef var player: Player = Player(),
        var gamesLost: Int = 0,
        var gamesWon: Int = 0,
        var rank: Int = 0
)