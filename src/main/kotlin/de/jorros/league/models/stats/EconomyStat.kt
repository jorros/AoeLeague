package de.jorros.league.models.stats

data class EconomyStat(
        var score: Int = 0,
        var foodCollected: Int = 0,
        var woodCollected: Int = 0,
        var stoneCollected: Int = 0,
        var goldCollected: Int = 0,
        var tributeSent: Int = 0,
        var tributeReceived: Int = 0,
        var tradeProfit: Int = 0,
        var relicGold: Int = 0
)