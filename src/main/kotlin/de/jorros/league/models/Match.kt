package de.jorros.league.models

import org.springframework.data.annotation.Id

data class Match(
        @Id var _id: String? = null,

        var league: String = "",
        var leg: Int = 0,
        var gameSettings: GameSettings = GameSettings(),
        var playTime: Int = 0,
        var players: List<PlayerInMatch> = listOf()
)