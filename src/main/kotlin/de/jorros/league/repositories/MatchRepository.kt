package de.jorros.league.repositories

import de.jorros.league.models.Match
import org.bson.types.ObjectId
import org.springframework.data.mongodb.repository.MongoRepository

interface MatchRepository : MongoRepository<Match, String> {
    fun findByLeague(league: ObjectId): List<Match>
}