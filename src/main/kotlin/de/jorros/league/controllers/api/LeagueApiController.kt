package de.jorros.league.controllers.api

import de.jorros.league.models.League
import de.jorros.league.repositories.LeagueRepository
import de.jorros.league.repositories.MatchRepository
import de.jorros.league.repositories.PlayerRepository
import org.bson.types.ObjectId
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.web.bind.annotation.PathVariable
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.web.bind.annotation.RequestMethod
import org.springframework.web.bind.annotation.RestController

@RestController
@RequestMapping("/api/league")
class LeagueApiController @Autowired constructor(val leagueRepository: LeagueRepository, val matchRepository: MatchRepository, val playerRepository: PlayerRepository) {
    @RequestMapping("/{name}", method = arrayOf(RequestMethod.GET))
    fun fetchAll(@PathVariable("name") name: String): League {
        return leagueRepository.findByName(name)
    }

    @RequestMapping("/", method = arrayOf(RequestMethod.POST))
    fun save(league: League) {
        leagueRepository.save(league)
    }

    @RequestMapping("/{name}/{player}", method = arrayOf(RequestMethod.GET))
    fun fetchPlayerInformation(@PathVariable("name") name: String, @PathVariable("player") playerName: String): Any {
        var league = leagueRepository.findByName(name)
        var leaguePlayer = league.players.first { it.player.name == playerName }
        var player = playerRepository.findByName(playerName)
        var matches = matchRepository.findByLeague(ObjectId(league._id)).filter { it.players.any { it.player._id == player._id } }

        return object {
            var name = leaguePlayer.player
            var rank = leaguePlayer.rank
            var avgTime = matches.fold(0) { total, next -> total + next.playTime } / matches.count()
            var lastMatches = matches.takeLast(5)
            var countMatches = matches.count()
        }
    }
}