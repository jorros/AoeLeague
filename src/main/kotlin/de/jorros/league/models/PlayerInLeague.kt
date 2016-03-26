package de.jorros.league.models

data class PlayerInLeague(
        var player: String = "",
        var gamesLost: Int = 0,
        var gamesWon: Int = 0,
        var rank: Int = 0
)