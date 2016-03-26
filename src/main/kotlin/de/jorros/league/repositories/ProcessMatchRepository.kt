package de.jorros.league.repositories

import de.jorros.league.models.ProcessMatch
import org.springframework.data.mongodb.repository.MongoRepository

interface ProcessMatchRepository : MongoRepository<ProcessMatch, String> {
}