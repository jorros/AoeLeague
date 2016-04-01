package de.jorros.league.controllers

import de.jorros.league.models.League
import de.jorros.league.repositories.ConfigRepository
import de.jorros.league.repositories.LeagueRepository
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.stereotype.Controller
import org.springframework.ui.Model
import org.springframework.web.bind.annotation.PathVariable
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.web.bind.annotation.RequestMethod

@Controller
@RequestMapping("/")
class LeagueController @Autowired constructor(
        val configRepository: ConfigRepository,
        val leagueRepository: LeagueRepository
) {
    @RequestMapping(value = "/", method = arrayOf(RequestMethod.GET))
    fun index(model: Model): String {
        return index(leagueId = "", model = model)
    }

    @RequestMapping(value = "/{leagueId}", method = arrayOf(RequestMethod.GET))
    fun index(@PathVariable leagueId: String, model: Model): String {
        var league: League;
        var config = configRepository.findAll().first()

        if(leagueId.isNotEmpty()) {
            league = leagueRepository.findOne(leagueId)
        } else {
            league = config.league
        }

        model.addAttribute("league", league)

        return "league/index"
    }
}