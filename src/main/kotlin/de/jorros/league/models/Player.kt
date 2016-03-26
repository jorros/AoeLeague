package de.jorros.league.models

import org.springframework.data.annotation.Id

data class Player(
        @Id var _id: String? = null,

        var name: String = ""
)