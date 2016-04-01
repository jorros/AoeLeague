package de.jorros.league.repositories

import de.jorros.league.models.Config
import org.springframework.data.mongodb.repository.MongoRepository

interface ConfigRepository : MongoRepository<Config, String> {
}