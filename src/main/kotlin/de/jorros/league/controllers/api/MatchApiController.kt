package de.jorros.league.controllers.api

import de.jorros.league.models.Match
import de.jorros.league.repositories.MatchRepository
import org.bson.types.ObjectId
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.web.bind.annotation.PathVariable
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.web.bind.annotation.RequestMethod
import org.springframework.web.bind.annotation.RestController

@RestController
@RequestMapping(value = "/api/match")
class MatchApiController @Autowired constructor(var matchRepository: MatchRepository) {
    @RequestMapping(value = "/league/{league}", method = arrayOf(RequestMethod.GET))
    fun findByLeague(@PathVariable league: String): List<Match> {
        return matchRepository.findByLeague(ObjectId(league))
    }

    @RequestMapping(value = "/{match}", method = arrayOf(RequestMethod.GET))
    fun findOne(@PathVariable match: String): Match {
        return matchRepository.findOne(match)
    }

    @RequestMapping(value = "/", method = arrayOf(RequestMethod.GET))
    fun findAll(): List<Match> {
        return matchRepository.findAll()
    }
}