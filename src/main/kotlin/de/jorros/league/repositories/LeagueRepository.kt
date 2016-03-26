package de.jorros.league.repositories

import de.jorros.league.models.League
import org.springframework.data.mongodb.repository.MongoRepository

interface LeagueRepository : MongoRepository<League, String> {
    fun findByName(name: String): League
}