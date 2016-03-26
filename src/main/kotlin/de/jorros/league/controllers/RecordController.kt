package de.jorros.league.controllers

import de.jorros.league.repositories.ProcessMatchRepository
import org.springframework.beans.factory.annotation.Autowired
import org.springframework.util.FileCopyUtils
import org.springframework.web.bind.annotation.*
import org.springframework.web.multipart.MultipartFile
import java.io.*

@RestController
@RequestMapping("/record")
class RecordController@Autowired constructor(val processMatchRepository: ProcessMatchRepository) {
    @RequestMapping(method = arrayOf(RequestMethod.POST), value = "/upload")
    fun uploadRecord(file: MultipartFile): Any {
        try {
            if (!file.isEmpty) {
                var stream = BufferedOutputStream(
                        FileOutputStream(File("/unprocessed/${file.originalFilename}")));
                FileCopyUtils.copy(file.getInputStream(), stream);
                stream.close();
            } else {
                return object {
                    var status = "Error"
                    var message = "Empty file"
                }
            }
        }
        catch(exception: Exception) {
            return object {
                var status = "Error"
                var message = exception.message
            }
        }

        return object { var status = "Ok" }
    }

    @RequestMapping(value = "/list", method = arrayOf(RequestMethod.GET))
    fun listUnprocessed(): Any {
        return processMatchRepository.findAll()
    }

    @RequestMapping(value = "/process", method = arrayOf(RequestMethod.GET))
    fun processBatch(): Any {
        var output = StringBuilder();

        try {
            var p = Runtime.getRuntime().exec("php runner.php", null, File("${System.getProperty("user.dir")}/analyser"));
            var input = BufferedReader(InputStreamReader(p.inputStream));
            input.forEachLine {
                output.append(it);
            }
            input.close();
        }
        catch (exception: Exception) {
            return object {
                var status = "Error"
                var message = exception.message
            }
        }

        return object {
            var status = "Ok"
            var message = output.toString()
        }
    }

    @RequestMapping(value = "/{id}/resolve", method = arrayOf(RequestMethod.POST))
    fun resolveMatch(@PathVariable("id") id: String, winner: Int) : Any {
        var match = processMatchRepository.findOne(id)

        if(match != null) {
            match.winner = match.players[winner]
            match.pause = false

            processMatchRepository.save(match);
        } else {
            return object {
                var status = "Error"
                var message = "Could not find unprocessed match with id $id"
            }
        }

        return object { var status = "Ok" }
    }
}