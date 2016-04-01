package de.jorros.league.models

import org.springframework.data.annotation.Id
import org.springframework.data.mongodb.core.mapping.DBRef

data class Match(
        @Id var _id: String? = null,

        @DBRef var league: League = League(),
        var leg: Int = 0,
        var gameSettings: GameSettings = GameSettings(),
        var playTime: Int = 0,
        var players: List<PlayerInMatch> = listOf()
)