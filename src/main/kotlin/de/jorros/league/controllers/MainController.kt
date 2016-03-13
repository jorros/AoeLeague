package de.jorros.league.controllers

import org.springframework.stereotype.Controller
import org.springframework.ui.Model
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.web.bind.annotation.RequestMethod

@Controller
@RequestMapping("/")
class MainController {
    @RequestMapping(value = "/", method = arrayOf(RequestMethod.GET))
    fun index(model: Model): String {
        return "index"
    }
}