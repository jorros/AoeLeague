package de.jorros.league.models

import de.jorros.league.models.stats.*
import org.springframework.data.mongodb.core.mapping.DBRef

data class PlayerInMatch(
        @DBRef var player: Player = Player(),
        var civilization: Int = 0,
        var feudalTime: Int = 0,
        var castleTime: Int = 0,
        var imperialTime: Int = 0,
        var resignTime: Int = 0,
        var colour: Int = 0,

        var buildings: Map<String, Int> = mapOf(),
        var research: Map<String, Int> = mapOf(),

        var winner: Boolean = false,
        var mvp: Boolean = false,
        var totalScore: Int = 0,
        var militaryStats: MilitaryStat = MilitaryStat(),
        var economyStats: EconomyStat = EconomyStat(),
        var techStats: TechStat = TechStat(),
        var societyStats: SocietyStat = SocietyStat()
)