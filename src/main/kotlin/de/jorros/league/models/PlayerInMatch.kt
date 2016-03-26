package de.jorros.league.models

data class PlayerInMatch(
        var player: String = "",
        var civilization: Int = 0,
        var feudalTime: Int = 0,
        var castleTime: Int = 0,
        var imperialTime: Int = 0,
        var resignTime: Int = 0,
        var colour: Int = 0,

        var buildings: Map<String, Int> = mapOf(),
        var research: Map<String, Int> = mapOf()
)