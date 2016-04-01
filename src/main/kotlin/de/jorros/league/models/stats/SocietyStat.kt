package de.jorros.league.models.stats

data class SocietyStat(
        var score: Int = 0,
        var totalWonders: Int = 0,
        var totalCastles: Int = 0,
        var relicsCaptured: Int = 0,
        var villagerHigh: Int = 0
)