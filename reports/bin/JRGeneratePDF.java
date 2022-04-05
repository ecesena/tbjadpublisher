/*
http://www.developer.com/java/data/article.php/3417381
http://groups.google.com/group/abhi_and_friends/web/jasperbyexample
http://wiki.rubyonrails.org/rails/pages/HowtoIntegrateJasperReports
*/

import com.mysql.jdbc.Driver;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

import net.sf.jasperreports.engine.*;
import java.util.HashMap;

import net.sf.jasperreports.engine.util.JRLoader;

public class JRGeneratePDF {
	public static void main(String[] args) {
		String dbServer = args[0];
		String dbName = args[1];
		String dbUser = args[2];
		String dbPass = args[3];
		String reportName = args[4];
		
		//Create Parameter Hash and fill
		HashMap params = new HashMap();
		for(int i=5; i<args.length; i++)
			params.put(args[i], args[++i]);
		
		
		JasperReport jasperReport;
		JasperPrint jasperPrint;
		Connection connection;
		try {
			// Connect to MySQL Database
			Class.forName("com.mysql.jdbc.Driver");
			Connection con=DriverManager.getConnection("jdbc:mysql://" + dbServer + "/" + dbName,dbUser,dbPass);
			
			//Compile report
			//jasperReport = JasperCompileManager.compileReport(reportName + ".jrxml");
			jasperReport = (JasperReport)JRLoader.loadObject(reportName + ".jasper");
			//Fill report with params and database connection
			jasperPrint = JasperFillManager.fillReport(jasperReport, params, con);
			
			//Export as PDF file
			JasperExportManager.exportReportToPdfFile(jasperPrint, reportName + ".pdf");
		} catch (JRException e) {
			e.printStackTrace();
		} catch (ClassNotFoundException classNotFoundException) {
			classNotFoundException.printStackTrace();
		} catch (SQLException sqlException) {
			sqlException.printStackTrace();
		}
	
	}
}