package de.jorros.league.repositories

import de.jorros.league.models.Player
import org.springframework.data.mongodb.repository.MongoRepository

interface PlayerRepository : MongoRepository<Player, String> {
    fun findByName(name: String): Player
}