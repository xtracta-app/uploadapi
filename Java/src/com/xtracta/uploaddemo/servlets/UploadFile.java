package com.xtracta.uploaddemo.servlets;

import java.io.*;
import java.util.List;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 * Servlet implementation class UploadFile
 */
@WebServlet("/UploadFile")
public class UploadFile extends HttpServlet {
	private static final long serialVersionUID = 1L;
       
    /**
     * @see HttpServlet#HttpServlet()
     */
    public UploadFile() {
        super();
        // TODO Auto-generated constructor stub
    }

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		PrintWriter out = response.getWriter();
		
        String charset = "UTF-8";
        File documentFile = new File("PATH_TO_YOUR_FILE");
        String requestURL = "https://api-app.xtracta.com/v1/documents/upload";
 
        try {
            MultipartUtility multipart = new MultipartUtility(requestURL, charset);
             
            multipart.addFormField("api_key", "YOUR_API_KEY");
            multipart.addFormField("workflow_id", "YOUR_WORKFLOW_ID");
             
            multipart.addFilePart("userfile", documentFile);
 
            List<String> resp = multipart.finish();
             
            out.println("SERVER REPLIED:");
             
            for (String line : resp) {
                out.println(line);
            }
        } catch (IOException ex) {
            out.println(ex);
        }
	}
}
