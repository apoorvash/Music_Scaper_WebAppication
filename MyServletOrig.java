import java.net.*;
import java.io.*;
import java.lang.*;
import org.w3c.dom.*;
import org.xml.sax.*;
import javax.xml.parsers.*;
import java.net.URL;
import java.io.InputStream;
import java.io.File;
import org.w3c.dom.Document;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;
import org.xml.sax.SAXException;
import org.xml.sax.SAXParseException; 
import javax.servlet.*;
import javax.servlet.http.*;


/** Servlet template from tutorial on servlets and JSP that
 *  appears at http://www.apl.jhu.edu/~hall/java/Servlet-Tutorial/
 *  1999 Marty Hall; may be freely used or adapted.
 */

public class MyServletOrig extends HttpServlet {
  public void doGet(HttpServletRequest request,
                    HttpServletResponse response)
      throws ServletException, IOException {
      
    // Use "request" to read incoming HTTP headers (e.g. cookies)
    // and HTML form data (e.g. data the user entered and submitted)
    
    // Use "response" to specify the HTTP response line and headers
    // (e.g. specifying the content type, setting cookies).
    response.setContentType("text/html; charset=UTF-8");
	request.setCharacterEncoding("UTF-8");
    PrintWriter out = response.getWriter();
	String title=request.getParameter("title");
	title=title.replaceAll(" ","+");
	title=URLEncoder.encode(title,"UTF-8");
	String type=request.getParameter("title_type");
	String turl="http://cs-server.usc.edu:17951/get_discography.php?title="+title+"&title_type="+type;
	URL url = new URL(turl);
	URLConnection connection = url.openConnection();
	connection.setAllowUserInteraction(false);
	connection.setRequestProperty("Accept-Charset", "UTF-8");	
	InputStream urlStream= url.openStream();	
			BufferedReader input = new BufferedReader( new InputStreamReader( connection.getInputStream(),"UTF-8") );
			String c=null;
			String xml_data="";
			
			while( ( c = input.readLine() ) != null) 
			{
				if(c!=null)
				xml_data=xml_data+c+"\n";
			}
			input.close();
			InputSource is = new InputSource(new StringReader(xml_data));
			is.setEncoding("UTF-8");
	
	try
		{
		
			DocumentBuilderFactory docBuild = DocumentBuilderFactory.newInstance(); 
			DocumentBuilder builder = docBuild.newDocumentBuilder();                        
			Document d = builder.parse( is );                                       
			d.getDocumentElement().normalize();
			String cover, name, title_al, artist, genre, year, details, sample, composer, song, performer;
			String jsonoutput="{\"results\":{\n\"result\":[\n";
			if( type.equals("artists") )
			{
				NodeList result_list = d.getElementsByTagName("result");
				int count= result_list.getLength();
				for (int i=0;i<count;i++)
				{
					Node root = result_list.item(i);
					Element mList = (Element) root;
					if(i != 0)
						jsonoutput = jsonoutput+",";
					cover = mList.getAttribute("cover");
					name = mList.getAttribute("name");
					genre = mList.getAttribute("genre");
					year = mList.getAttribute("year");
					details = mList.getAttribute("details");
					
					jsonoutput = jsonoutput+"{\"cover\":\""+cover+"\",\"name\":\""+name+"\",\"genre\":\""+genre+"\",\"year\":\""+year+"\",\"details\":\""+details+"\"}\n";
				}
				jsonoutput = jsonoutput+"]}}";
				out.println(jsonoutput);
			}	
			
			if( type.equals("albums") )
			{
				NodeList result_list = d.getElementsByTagName("result");
				int count= result_list.getLength();
				for (int i=0;i<count;i++)
				{
					Node root = result_list.item(i);
					Element mList = (Element) root;
					if(i != 0)
						jsonoutput = jsonoutput+",";
					cover = mList.getAttribute("cover");
					title_al = mList.getAttribute("title");
					artist = mList.getAttribute("artist");
					genre = mList.getAttribute("genre");
					year = mList.getAttribute("year");
					details = mList.getAttribute("details");
					jsonoutput = jsonoutput+"{\"cover\":\""+cover+"\",\"title\":\""+title_al+"\",\"artist\":\""+artist+"\",\"genre\":\""+genre+"\",\"year\":\""+year+"\",\"details\":\""+details+"\"}\n";
				}
				jsonoutput = jsonoutput+"]}}";
				out.println(jsonoutput);
			}	
			
			if( type.equals("song") )
			{
				NodeList result_list = d.getElementsByTagName("result");
				int count= result_list.getLength();
				for (int i=0;i<count;i++)
				{
					Node root = result_list.item(i);
					Element mList = (Element) root;
					if(i != 0)
						jsonoutput = jsonoutput+",";
					sample = mList.getAttribute("sample");
					title_al = mList.getAttribute("title");
					performer = mList.getAttribute("performer");
					composer = mList.getAttribute("composer");
					song = mList.getAttribute("song");
					jsonoutput = jsonoutput+"{\"sample\":\""+sample+"\",\"title\":\""+title_al+"\",\"performer\":\""+performer+"\",\"composer\":\""+composer+"\",\"song\":\""+song+"\"}\n";
				}
				jsonoutput = jsonoutput+"]}}";
				out.println(jsonoutput);
			}	
				
		} 
		catch(MalformedURLException e)
		{
			out.println("Malformed URL Exception: "+e.getMessage());
		}
		catch(IOException e)
		{
			out.println("IO Exception: "+e.getMessage());
		}
		catch (Exception e)
        {
            out.println("Exception: "+e.getMessage());
        }
	
  }
}
