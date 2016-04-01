package de.jorros.league.models.stats

data class MilitaryStat(
        var score: Int = 0,
        var unitsKilled: Int = 0,
        var unitsLost: Int = 0,
        var buildingsRazed: Int = 0,
        var buildingsLost: Int = 0,
        var unitsConverted: Int = 0
)