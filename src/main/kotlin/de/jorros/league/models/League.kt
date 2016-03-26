package de.jorros.league.models

import org.springframework.data.annotation.Id

data class League(
        @Id var _id: String? = null,

        var name: String = "",
        var gameSettings: GameSettings = GameSettings(),
        var mode: String = "",
        var players: List<PlayerInLeague> = listOf()
)