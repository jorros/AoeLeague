package de.jorros.league.models

data class GameSettings(
        var gameType: Int = 0,
        var mapStyle: Int = 0,
        var gameSpeed: Int = 0,
        var revealMap: Int = 0,
        var mapSize: Int = 0,
        var scenario: Boolean = false,
        var mapName: String = "",
        var popLimit: Int = 0
)