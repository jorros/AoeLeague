package de.jorros.league.controllers

import de.jorros.league.models.League
import de.jorros.league.repositories.LeagueRepository
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.web.bind.annotation.PathVariable
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.web.bind.annotation.RequestMethod
import org.springframework.web.bind.annotation.RestController

@RestController
@RequestMapping("/league")
class LeagueController @Autowired constructor(val leagueRepository: LeagueRepository) {
    @RequestMapping("/{name}", method = arrayOf(RequestMethod.GET))
    fun fetchAll(@PathVariable("name")name: String): League {
        return leagueRepository.findByName(name)
    }

    @RequestMapping("/", method = arrayOf(RequestMethod.POST))
    fun save(league: League) {
        leagueRepository.save(league)
    }
}