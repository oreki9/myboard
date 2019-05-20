# myboard
pengingat robotics notes hitbox
make chat game with smart ai


import java.awt.*;
import java.awt.event.*;

public class LatihGUI
{
	private Frame frm;
	private Panel pnl;
	public Label lblJudul;
	private Button tombolX;
	private Button tombolY;
	
	public void SiapkanGUI()
	{
		frm = new Frame("Ini Frame");
		frm.setSize(400,400);
		frm.setLayout(new GridLayout(3,1));
		pnl = new Panel();
		pnl.setLayout(new FlowLayout());
		frm.add(pnl);
		frm.addWindowListener(new WindowAdapter()
		{
			public void windowClosing(WindowEvent e)
			{
				System.exit(0);
			}
		});
		lblJudul = new Label();
		lblJudul.setAlignment(Label.CENTER);
		lblJudul.setSize(100,100);
		lblJudul.setText("INI JUDUL");
		frm.add(lblJudul);
		//button
		tombolX = new Button("Tekan Aku");
		tombolX.setActionCommand("Ok");
		tombolX.addActionListener(new ButtonClickListener());
		pnl.add(tombolX);
		tombolY = new Button("Kembalikan Aku");
		tombolY.setActionCommand("Return");
		tombolY.addActionListener(new ButtonClickListener());
		pnl.add(tombolY);
		frm.setVisible(true);
	}
	
	public static void main(String[] a)
	{
		LatihGUI guiObjek = new LatihGUI();
		guiObjek.SiapkanGUI();
	}

	private class ButtonClickListener implements ActionListener
	{
		public void actionPerformed(ActionEvent e)
		{
			String com = e.getActionCommand();
			if(com.equals("Ok"))
			{
				lblJudul.setText("ok di klik");
				System.out.println("ok di klik");
			}
			if(com.equals("Return"))
			{
				lblJudul.setText("INI JUDUL");
				System.out.println("INI JUDUL");
			}
			
		}
	}	
}

