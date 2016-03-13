package de.jorros.league.models

import org.springframework.data.annotation.Id

data class Player(
        @Id var _id: String? = null,

        var name: String = "",
        var league: String = "",

        var gamesLost: Int = 0,
        var gamesWon: Int = 0,
        var unitsKilled: Int = 0,
        var unitsLost: Int = 0
) {
    val kd: Double
        get() = unitsKilled.toDouble() / unitsLost.toDouble()
}