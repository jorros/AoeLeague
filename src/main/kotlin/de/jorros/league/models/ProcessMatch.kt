package de.jorros.league.models

import org.springframework.data.annotation.Id

data class ProcessMatch(
        @Id var _id: String? = null,

        var filename: String = "",
        var players: List<String> = listOf(),
        var winner: String = "",
        var pause: Boolean = false
)