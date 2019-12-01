import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class MusicServer
{
	private Connection database;

	public static void main (String[] args)
	{
		MusicServer server = new MusicServer();
		if (args.length != 0)
			server.connect(args[0]);
		else server.connect("./music_library.db");	//set to C:/whatever if needed on windows

		System.out.println(server.selectOwnedSongs("1"));
		System.out.println(server.selectGenreSongs("Jazz"));
	}

	//connects to a database. must be run first!
	private void connect(String databaseLocation)
	{
		try
		{
			database = DriverManager.getConnection("jdbc:sqlite:" + databaseLocation);
			System.out.println("Connected to the database!");
		}
		catch (SQLException noDatabase)
		{
			System.out.println("Unable to connect to " + databaseLocation + "!");
			System.out.println(noDatabase.getMessage());
			System.exit(1);
		}
	}

	//returns a string of all the songs in a given genre
	private String selectGenreSongs (String genre)
	{
		try
		{
			ResultSet results = database.createStatement().executeQuery("SELECT DISTINCT Name FROM SONG " +
				"WHERE Genre = '" + genre + "'");
			StringBuilder output = new StringBuilder();
			while (results.next())
				output.append(results.getString("Name")).append("\n");
			if (output.length() == 0)
				output.append("There are no songs listed in that genre.");
			return output.toString();
		}
		catch (SQLException e)
		{
			System.out.println(e.getMessage());
			return "Unable to find songs in that genre. See server log for details.";
		}
	}
	//returns a string of all the songs owned by the given user
	private String selectOwnedSongs (String userId)
	{
		try
		{
			ResultSet results = database.createStatement().executeQuery("SELECT DISTINCT SONG.Name FROM SONG, OWNS " +
					"WHERE User_ID = " + userId + " AND OWNS.Song_ID = SONG.Song_ID;");
			StringBuilder output = new StringBuilder();
			while (results.next())
				output.append(results.getString("Name")).append("\n");
			if (output.length() == 0)
				output.append("You don't own any music yet!");
			return output.toString();
		}
		catch (SQLException e)
		{
			System.out.println(e.getMessage());
			return "Unable to find your songs. See server log for details.";
		}
	}

	//inserts a relation into the OWNS table
	private String purchaseSong (String userId, String songId)
	{
		try
		{
			PreparedStatement statement = database.prepareStatement("INSERT INTO Owns(User_ID, Song_ID) VALUES(?, ?)");
			statement.setString(1, userId);
			statement.setString(2, songId);
			statement.executeUpdate();
			//put any methods to bill the user here!
			return "Purchase successful!";
		}
		catch (SQLException e)
		{
			System.out.println(e.getMessage());
			return "Purchase unsuccessful. See server log for details.";
		}
	}
}
