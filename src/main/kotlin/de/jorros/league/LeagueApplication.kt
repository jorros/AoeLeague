package de.jorros.league

import org.springframework.boot.SpringApplication
import org.springframework.boot.autoconfigure.SpringBootApplication
import org.springframework.boot.builder.SpringApplicationBuilder
import org.springframework.boot.context.web.SpringBootServletInitializer

@SpringBootApplication
open class LeagueApplication : SpringBootServletInitializer() {
    override fun configure(builder: SpringApplicationBuilder?): SpringApplicationBuilder? {
        return builder!!.sources(LeagueApplication::class.java)
    }
}

fun main(args: Array<String>) {
    SpringApplication.run(LeagueApplication::class.java, *args)
}
