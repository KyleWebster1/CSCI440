import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.ServerSocket;
import java.net.Socket;
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

		//loop until killed
		while (true)
		{
			try (ServerSocket sock = new ServerSocket(42101);
				Socket client = sock.accept();
				PrintWriter out = new PrintWriter(client.getOutputStream(), true);
				BufferedReader in = new BufferedReader(new InputStreamReader(client.getInputStream())))
			{
				System.out.println("Connection from " + client.getInetAddress() + " established!");

				String inLine;
				while ((inLine = in.readLine()) != null)
				{
					String[] words = inLine.split(",");
					switch (words[0])
					{
						case "bye":
							sock.close();
							break;
						case "genre":
							out.println(server.selectGenreSongs(words[1]));
							break;
						case "owned":
							out.println(server.selectOwnedSongs(words[1]));
							break;
						case "user_playlists":
							out.println(server.selectUserPlaylists(words[1]));
							break;
						case "similar_playlists":
							out.println(server.selectSimilarPlaylists(words[1]));
							break;
						case "distributor":
							out.println(server.selectDistributor(words[1]));
							break;
						case "add_user":
							out.println(server.addUser(words[1], words[2], words[3], words[4]));
							break;
						case "purchase":
							out.println(server.purchaseSong(words[1], words[2]));
							break;
						default:
							out.println("Unknown command - " + words[0]);
					}
				}
			}
			catch (IOException communicationError)
			{
				System.out.println("An IOException occurred. Starting over...");
			}
		}
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

	//returns all the songs in a given genre
	private String selectGenreSongs (String genre)
	{
		try
		{
			ResultSet results = database.createStatement().executeQuery("SELECT DISTINCT Name " +
				"FROM SONG " +
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
	//returns all the songs owned by the given user
	private String selectOwnedSongs (String userId)
	{
		try
		{
			ResultSet results = database.createStatement().executeQuery("SELECT DISTINCT SONG.Name " +
				"FROM SONG, OWNS " +
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
	//returns all the playlists created by a user and the songs contained by each
	private String selectUserPlaylists (String userId)
	{
		try
		{
			ResultSet results = database.createStatement().executeQuery("SELECT PLAYLIST.Name, SONG.Name AS Sname " +
				"FROM PLAYLIST, SONG, HOLDS " +
				"WHERE PLAYLIST.User_ID = " + userId + " AND PLAYLIST.Playlist_ID = HOLDS.Playlist_ID AND SONG.Song_ID = HOLDS.Song_ID " +
				"ORDER BY PLAYLIST.Name LIMIT 5;");
			StringBuilder output = new StringBuilder();
			while (results.next())
			{
				output.append(results.getString("Name")).append(" - ");
				output.append(results.getString("Sname")).append("\n");
			}
			if (output.length() == 0)
				output.append("This user does not have any playlists!");
			return output.toString();
		}
		catch (SQLException e)
		{
			System.out.println(e.getMessage());
			return "Unable to find the user and/or playlists. See server log for details.";
		}
	}
	//returns all the playlists containing a given song and the names of the users they were created by
	private String selectSimilarPlaylists (String songId)
	{
		try
		{
			ResultSet results = database.createStatement().executeQuery("SELECT PLAYLIST.Name, USER.UserID " +
				"FROM PLAYLIST, HOLDS, SONG, USER " +
				"WHERE SONG.Song_ID =  " + songId + " AND SONG.Song_ID = HOLDS.Song_ID " +
				"AND HOLDS.Playlist_ID = PLAYLIST.Playlist_ID AND PLAYLIST.User_ID = USER.User_ID");
			StringBuilder output = new StringBuilder();
			while (results.next())
			{
				output.append(results.getString("Name")).append(" by ");
				output.append(results.getString("UserID")).append("\n");
			}
			if (output.length() == 0)
				output.append("No playlists contain your song.");
			return output.toString();
		}
		catch (SQLException e)
		{
			System.out.println(e.getMessage());
			return "Unable to find playlists. See server log for details.";
		}
	}
	//returns the name of the record company that distributes a given song
	private String selectDistributor (String songId)
	{
		try
		{
			ResultSet results = database.createStatement().executeQuery("SELECT Name FROM RECORD_COMPANY, DISTRIBUTES, DISTRIBUTOR " +
				"WHERE DISTRIBUTES.Song_ID = " + songId + " AND DISTRIBUTES.Distributor_ID = DISTRIBUTOR.Distributor_ID " +
				"AND DISTRIBUTOR.Company_ID = RECORD_COMPANY.Company_ID");
			StringBuilder output = new StringBuilder();
			while (results.next())
				output.append(results.getString("Name")).append("\n");
			if (output.length() == 0)
				output.append("There is no record company listed for that song.");
			return output.toString();
		}
		catch (SQLException e)
		{
			System.out.println(e.getMessage());
			return "Unable to find the distributor. See server log for details.";
		}
	}

	//adds a new user to the database
	private String addUser (String userId, String firstName, String lastName, String email)
	{
		try
		{
			PreparedStatement statement = database.prepareStatement("INSERT INTO USER(User_ID, First_name, Last_name, Email) " +
				"VALUES(?, ?, ?, ?)");
			statement.setString(1, userId);
			statement.setString(2, firstName);
			statement.setString(3, lastName);
			statement.setString(4, email);
			statement.executeUpdate();
			return "Created new user. Welcome, " + (firstName.equals("") ? userId : firstName) + "!";
		}
		catch (SQLException e)
		{
			System.out.println(e.getMessage());
			return "User creation unsuccessful - is your username already taken? See server log for details.";
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
