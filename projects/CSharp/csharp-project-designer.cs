namespace WindowsFormsApplication1
{
    partial class Form1
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.button1 = new System.Windows.Forms.Button();
            this.t_calaulating = new System.Windows.Forms.TextBox();
            this.t_result = new System.Windows.Forms.TextBox();
            this.label1 = new System.Windows.Forms.Label();
            this.label2 = new System.Windows.Forms.Label();
            this.button2 = new System.Windows.Forms.Button();
            this.button3 = new System.Windows.Forms.Button();
            this.button4 = new System.Windows.Forms.Button();
            this.button5 = new System.Windows.Forms.Button();
            this.button6 = new System.Windows.Forms.Button();
            this.button7 = new System.Windows.Forms.Button();
            this.button8 = new System.Windows.Forms.Button();
            this.button9 = new System.Windows.Forms.Button();
            this.button10 = new System.Windows.Forms.Button();
            this.button11 = new System.Windows.Forms.Button();
            this.button12 = new System.Windows.Forms.Button();
            this.button13 = new System.Windows.Forms.Button();
            this.button14 = new System.Windows.Forms.Button();
            this.button15 = new System.Windows.Forms.Button();
            this.button16 = new System.Windows.Forms.Button();
            this.button17 = new System.Windows.Forms.Button();
            this.SuspendLayout();
            // 
            // button1
            // 
            this.button1.BackColor = System.Drawing.Color.DarkKhaki;
            this.button1.Dock = System.Windows.Forms.DockStyle.Bottom;
            this.button1.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button1.ForeColor = System.Drawing.Color.Cornsilk;
            this.button1.Location = new System.Drawing.Point(0, 516);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(655, 48);
            this.button1.TabIndex = 0;
            this.button1.Text = "=";
            this.button1.UseVisualStyleBackColor = false;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // t_calaulating
            // 
            this.t_calaulating.BackColor = System.Drawing.Color.IndianRed;
            this.t_calaulating.Dock = System.Windows.Forms.DockStyle.Top;
            this.t_calaulating.ForeColor = System.Drawing.SystemColors.ActiveCaptionText;
            this.t_calaulating.Location = new System.Drawing.Point(0, 0);
            this.t_calaulating.Name = "t_calaulating";
            this.t_calaulating.ReadOnly = true;
            this.t_calaulating.Size = new System.Drawing.Size(655, 24);
            this.t_calaulating.TabIndex = 1;
            // 
            // t_result
            // 
            this.t_result.BackColor = System.Drawing.Color.Aqua;
            this.t_result.Dock = System.Windows.Forms.DockStyle.Top;
            this.t_result.ForeColor = System.Drawing.Color.Black;
            this.t_result.Location = new System.Drawing.Point(0, 24);
            this.t_result.Name = "t_result";
            this.t_result.ReadOnly = true;
            this.t_result.Size = new System.Drawing.Size(655, 24);
            this.t_result.TabIndex = 2;
            this.t_result.Text = "0";
            this.t_result.TextChanged += new System.EventHandler(this.textBox2_TextChanged);
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.BackColor = System.Drawing.Color.PaleVioletRed;
            this.label1.Font = new System.Drawing.Font("Tahoma", 10F);
            this.label1.ForeColor = System.Drawing.SystemColors.ButtonHighlight;
            this.label1.Location = new System.Drawing.Point(555, 3);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(89, 21);
            this.label1.TabIndex = 3;
            this.label1.Text = "calculating";
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.BackColor = System.Drawing.Color.Cyan;
            this.label2.Font = new System.Drawing.Font("Tahoma", 10F);
            this.label2.ForeColor = System.Drawing.Color.Cornsilk;
            this.label2.Location = new System.Drawing.Point(610, 27);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(22, 21);
            this.label2.TabIndex = 17;
            this.label2.Text = "=";
            // 
            // button2
            // 
            this.button2.BackColor = System.Drawing.Color.DarkRed;
            this.button2.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button2.Font = new System.Drawing.Font("Times New Roman", 10F);
            this.button2.ForeColor = System.Drawing.Color.Cornsilk;
            this.button2.Location = new System.Drawing.Point(578, 130);
            this.button2.Name = "button2";
            this.button2.Size = new System.Drawing.Size(54, 42);
            this.button2.TabIndex = 18;
            this.button2.Text = "1";
            this.button2.UseVisualStyleBackColor = false;
            this.button2.Click += new System.EventHandler(this.number_click);
            // 
            // button3
            // 
            this.button3.BackColor = System.Drawing.Color.DarkRed;
            this.button3.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button3.Font = new System.Drawing.Font("Times New Roman", 10F);
            this.button3.ForeColor = System.Drawing.Color.Cornsilk;
            this.button3.Location = new System.Drawing.Point(578, 376);
            this.button3.Name = "button3";
            this.button3.Size = new System.Drawing.Size(54, 42);
            this.button3.TabIndex = 19;
            this.button3.Text = ".";
            this.button3.UseVisualStyleBackColor = false;
            this.button3.Click += new System.EventHandler(this.number_click);
            // 
            // button4
            // 
            this.button4.BackColor = System.Drawing.Color.DarkRed;
            this.button4.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button4.Font = new System.Drawing.Font("Times New Roman", 10F);
            this.button4.ForeColor = System.Drawing.Color.Cornsilk;
            this.button4.Location = new System.Drawing.Point(578, 294);
            this.button4.Name = "button4";
            this.button4.Size = new System.Drawing.Size(54, 42);
            this.button4.TabIndex = 20;
            this.button4.Text = "7";
            this.button4.UseVisualStyleBackColor = false;
            this.button4.Click += new System.EventHandler(this.number_click);
            // 
            // button5
            // 
            this.button5.BackColor = System.Drawing.Color.DarkRed;
            this.button5.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button5.Font = new System.Drawing.Font("Times New Roman", 10F);
            this.button5.ForeColor = System.Drawing.Color.Cornsilk;
            this.button5.Location = new System.Drawing.Point(578, 212);
            this.button5.Name = "button5";
            this.button5.Size = new System.Drawing.Size(54, 42);
            this.button5.TabIndex = 21;
            this.button5.Text = "4";
            this.button5.UseVisualStyleBackColor = false;
            this.button5.Click += new System.EventHandler(this.number_click);
            // 
            // button6
            // 
            this.button6.BackColor = System.Drawing.Color.DarkRed;
            this.button6.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button6.Font = new System.Drawing.Font("Times New Roman", 10F);
            this.button6.ForeColor = System.Drawing.Color.Cornsilk;
            this.button6.Location = new System.Drawing.Point(276, 212);
            this.button6.Name = "button6";
            this.button6.Size = new System.Drawing.Size(54, 42);
            this.button6.TabIndex = 25;
            this.button6.Text = "6";
            this.button6.UseVisualStyleBackColor = false;
            this.button6.Click += new System.EventHandler(this.number_click);
            // 
            // button7
            // 
            this.button7.BackColor = System.Drawing.Color.DarkRed;
            this.button7.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button7.Font = new System.Drawing.Font("Times New Roman", 10F);
            this.button7.ForeColor = System.Drawing.Color.Cornsilk;
            this.button7.Location = new System.Drawing.Point(276, 300);
            this.button7.Name = "button7";
            this.button7.Size = new System.Drawing.Size(54, 42);
            this.button7.TabIndex = 24;
            this.button7.Text = "9";
            this.button7.UseVisualStyleBackColor = false;
            // 
            // button8
            // 
            this.button8.BackColor = System.Drawing.Color.MediumBlue;
            this.button8.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button8.Font = new System.Drawing.Font("Times New Roman", 15F);
            this.button8.ForeColor = System.Drawing.Color.Cornsilk;
            this.button8.Location = new System.Drawing.Point(104, 385);
            this.button8.Name = "button8";
            this.button8.Size = new System.Drawing.Size(54, 42);
            this.button8.TabIndex = 23;
            this.button8.Text = "/";
            this.button8.UseVisualStyleBackColor = false;
            this.button8.Click += new System.EventHandler(this.pro_click);
            // 
            // button9
            // 
            this.button9.BackColor = System.Drawing.Color.DarkRed;
            this.button9.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button9.Font = new System.Drawing.Font("Times New Roman", 10F);
            this.button9.ForeColor = System.Drawing.Color.Cornsilk;
            this.button9.Location = new System.Drawing.Point(276, 130);
            this.button9.Name = "button9";
            this.button9.Size = new System.Drawing.Size(54, 42);
            this.button9.TabIndex = 22;
            this.button9.Text = "3";
            this.button9.UseVisualStyleBackColor = false;
            this.button9.Click += new System.EventHandler(this.number_click);
            // 
            // button10
            // 
            this.button10.BackColor = System.Drawing.Color.MediumBlue;
            this.button10.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button10.Font = new System.Drawing.Font("Times New Roman", 15F);
            this.button10.ForeColor = System.Drawing.Color.Cornsilk;
            this.button10.Location = new System.Drawing.Point(104, 213);
            this.button10.Name = "button10";
            this.button10.Size = new System.Drawing.Size(54, 42);
            this.button10.TabIndex = 29;
            this.button10.Text = "-";
            this.button10.UseVisualStyleBackColor = false;
            this.button10.Click += new System.EventHandler(this.pro_click);
            // 
            // button11
            // 
            this.button11.BackColor = System.Drawing.Color.MediumBlue;
            this.button11.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button11.Font = new System.Drawing.Font("Times New Roman", 15F);
            this.button11.ForeColor = System.Drawing.Color.Cornsilk;
            this.button11.Location = new System.Drawing.Point(104, 296);
            this.button11.Name = "button11";
            this.button11.Size = new System.Drawing.Size(54, 42);
            this.button11.TabIndex = 28;
            this.button11.Text = "*";
            this.button11.UseVisualStyleBackColor = false;
            this.button11.Click += new System.EventHandler(this.pro_click);
            // 
            // button12
            // 
            this.button12.BackColor = System.Drawing.Color.Tomato;
            this.button12.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button12.Font = new System.Drawing.Font("Times New Roman", 10F);
            this.button12.ForeColor = System.Drawing.Color.Cornsilk;
            this.button12.Location = new System.Drawing.Point(276, 385);
            this.button12.Name = "button12";
            this.button12.Size = new System.Drawing.Size(54, 42);
            this.button12.TabIndex = 27;
            this.button12.Text = "AC";
            this.button12.UseVisualStyleBackColor = false;
            this.button12.Click += new System.EventHandler(this.button12_Click);
            // 
            // button13
            // 
            this.button13.BackColor = System.Drawing.Color.MediumBlue;
            this.button13.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button13.Font = new System.Drawing.Font("Times New Roman", 15F);
            this.button13.ForeColor = System.Drawing.Color.Cornsilk;
            this.button13.Location = new System.Drawing.Point(104, 130);
            this.button13.Name = "button13";
            this.button13.Size = new System.Drawing.Size(54, 42);
            this.button13.TabIndex = 26;
            this.button13.Text = "+";
            this.button13.UseVisualStyleBackColor = false;
            this.button13.Click += new System.EventHandler(this.pro_click);
            // 
            // button14
            // 
            this.button14.BackColor = System.Drawing.Color.DarkRed;
            this.button14.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button14.Font = new System.Drawing.Font("Times New Roman", 10F);
            this.button14.ForeColor = System.Drawing.Color.Cornsilk;
            this.button14.Location = new System.Drawing.Point(427, 212);
            this.button14.Name = "button14";
            this.button14.Size = new System.Drawing.Size(54, 42);
            this.button14.TabIndex = 33;
            this.button14.Text = "5";
            this.button14.UseVisualStyleBackColor = false;
            this.button14.Click += new System.EventHandler(this.number_click);
            // 
            // button15
            // 
            this.button15.BackColor = System.Drawing.Color.DarkRed;
            this.button15.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button15.Font = new System.Drawing.Font("Times New Roman", 10F);
            this.button15.ForeColor = System.Drawing.Color.Cornsilk;
            this.button15.Location = new System.Drawing.Point(427, 294);
            this.button15.Name = "button15";
            this.button15.Size = new System.Drawing.Size(54, 42);
            this.button15.TabIndex = 32;
            this.button15.Text = "8";
            this.button15.UseVisualStyleBackColor = false;
            this.button15.Click += new System.EventHandler(this.number_click);
            // 
            // button16
            // 
            this.button16.BackColor = System.Drawing.Color.DarkRed;
            this.button16.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button16.Font = new System.Drawing.Font("Times New Roman", 10F);
            this.button16.ForeColor = System.Drawing.Color.Cornsilk;
            this.button16.Location = new System.Drawing.Point(427, 376);
            this.button16.Name = "button16";
            this.button16.Size = new System.Drawing.Size(54, 42);
            this.button16.TabIndex = 31;
            this.button16.Text = "0";
            this.button16.UseVisualStyleBackColor = false;
            this.button16.Click += new System.EventHandler(this.number_click);
            // 
            // button17
            // 
            this.button17.BackColor = System.Drawing.Color.DarkRed;
            this.button17.FlatStyle = System.Windows.Forms.FlatStyle.Flat;
            this.button17.Font = new System.Drawing.Font("Times New Roman", 10F);
            this.button17.ForeColor = System.Drawing.Color.Cornsilk;
            this.button17.Location = new System.Drawing.Point(427, 130);
            this.button17.Name = "button17";
            this.button17.Size = new System.Drawing.Size(54, 42);
            this.button17.TabIndex = 30;
            this.button17.Text = "2";
            this.button17.UseVisualStyleBackColor = false;
            this.button17.Click += new System.EventHandler(this.number_click);
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(7F, 16F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.BackColor = System.Drawing.Color.CornflowerBlue;
            this.ClientSize = new System.Drawing.Size(655, 564);
            this.Controls.Add(this.button14);
            this.Controls.Add(this.button15);
            this.Controls.Add(this.button16);
            this.Controls.Add(this.button17);
            this.Controls.Add(this.button10);
            this.Controls.Add(this.button11);
            this.Controls.Add(this.button12);
            this.Controls.Add(this.button13);
            this.Controls.Add(this.button6);
            this.Controls.Add(this.button7);
            this.Controls.Add(this.button8);
            this.Controls.Add(this.button9);
            this.Controls.Add(this.button5);
            this.Controls.Add(this.button4);
            this.Controls.Add(this.button3);
            this.Controls.Add(this.button2);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.t_result);
            this.Controls.Add(this.t_calaulating);
            this.Controls.Add(this.button1);
            this.MaximizeBox = false;
            this.MinimizeBox = false;
            this.Name = "Form1";
            this.RightToLeft = System.Windows.Forms.RightToLeft.Yes;
            this.RightToLeftLayout = true;
            this.Text = "الة حاسبة";
            this.Load += new System.EventHandler(this.Form1_Load);
            this.Click += new System.EventHandler(this.number_click);
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion

        private System.Windows.Forms.Button button1;
        private System.Windows.Forms.TextBox t_calaulating;
        private System.Windows.Forms.TextBox t_result;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.Button button2;
        private System.Windows.Forms.Button button3;
        private System.Windows.Forms.Button button4;
        private System.Windows.Forms.Button button5;
        private System.Windows.Forms.Button button6;
        private System.Windows.Forms.Button button7;
        private System.Windows.Forms.Button button8;
        private System.Windows.Forms.Button button9;
        private System.Windows.Forms.Button button10;
        private System.Windows.Forms.Button button11;
        private System.Windows.Forms.Button button12;
        private System.Windows.Forms.Button button13;
        private System.Windows.Forms.Button button14;
        private System.Windows.Forms.Button button15;
        private System.Windows.Forms.Button button16;
        private System.Windows.Forms.Button button17;
    }
}

