package de.jorros.league.controllers

import de.jorros.league.models.Player
import de.jorros.league.repositories.PlayerRepository
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.web.bind.annotation.RequestMethod
import org.springframework.web.bind.annotation.RestController
import java.util.*

@RestController
@RequestMapping("/ranking")
class RankingController @Autowired constructor(val playerRepository: PlayerRepository) {
    @RequestMapping("/", method = arrayOf(RequestMethod.GET))
    fun fetchAll(): Any {
        var result = playerRepository.findAll()

        result.sortWith(Comparator<Player> { x, y ->
            if(x.gamesWon == y.gamesWon) {
                if(x.gamesLost == y.gamesLost) {
                    return@Comparator (y.kd - x.kd).toInt()
                }

                return@Comparator x.gamesLost - y.gamesLost
            }

            y.gamesWon - x.gamesWon
        })

        return object { var players = result }
    }
}