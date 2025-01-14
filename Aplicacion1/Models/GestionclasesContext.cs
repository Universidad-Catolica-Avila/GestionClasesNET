using System;
using System.Collections.Generic;
using Microsoft.EntityFrameworkCore;

namespace Aplicacion1.Models;

public partial class GestionclasesContext : DbContext
{
    public GestionclasesContext()
    {
    }

    public GestionclasesContext(DbContextOptions<GestionclasesContext> options)
        : base(options)
    {
    }

    public virtual DbSet<Horarioclase> Horarioclases { get; set; }

    public virtual DbSet<Personalpospro> Personalpospros { get; set; }

    public virtual DbSet<Registroclasesemanal> Registroclasesemanals { get; set; }

    public virtual DbSet<Tecnico> Tecnicos { get; set; }

    public virtual DbSet<Usuario> Usuarios { get; set; }

    protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
#warning To protect potentially sensitive information in your connection string, you should move it out of source code. You can avoid scaffolding the connection string by using the Name= syntax to read it from configuration - see https://go.microsoft.com/fwlink/?linkid=2131148. For more guidance on storing connection strings, see https://go.microsoft.com/fwlink/?LinkId=723263.
        => optionsBuilder.UseSqlServer("Server=10.1.0.6;Database=gestionclases;User Id=gestionclases;Password=gestionclases;TrustServerCertificate=True;");

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.Entity<Horarioclase>(entity =>
        {
            entity.HasKey(e => e.IdHorario).HasName("PK__horarioc__DE60F33A0FBA4EBF");

            entity.ToTable("horarioclases");

            entity.Property(e => e.IdHorario).HasColumnName("idHorario");
            entity.Property(e => e.Asignatura)
                .HasMaxLength(150)
                .IsUnicode(false)
                .HasColumnName("asignatura");
            entity.Property(e => e.Aula)
                .HasMaxLength(40)
                .HasColumnName("aula");
            entity.Property(e => e.CodigoPad)
                .HasMaxLength(20)
                .HasColumnName("codigoPAD");
            entity.Property(e => e.Curso)
                .HasMaxLength(60)
                .HasColumnName("curso");
            entity.Property(e => e.Dia)
                .HasMaxLength(50)
                .HasColumnName("dia");
            entity.Property(e => e.EstadoHorario).HasColumnName("estadoHorario");
            entity.Property(e => e.FechaDeInserccion).HasColumnType("datetime");
            entity.Property(e => e.Hora)
                .HasPrecision(0)
                .HasColumnName("hora");
            entity.Property(e => e.Profesor)
                .HasMaxLength(150)
                .HasColumnName("profesor");
            entity.Property(e => e.Semestre)
                .HasMaxLength(50)
                .HasColumnName("semestre");
            entity.Property(e => e.TipoPad)
                .HasMaxLength(50)
                .HasColumnName("tipoPAD");
            entity.Property(e => e.TipoTitulacion)
                .HasMaxLength(50)
                .HasColumnName("tipoTitulacion");
            entity.Property(e => e.Titulacion)
                .HasMaxLength(60)
                .HasColumnName("titulacion");
        });

        modelBuilder.Entity<Personalpospro>(entity =>
        {
            entity.HasKey(e => e.IdPersonal).HasName("PK__personal__D840C9FD71613927");

            entity.ToTable("personalpospro");

            entity.Property(e => e.IdPersonal)
                .ValueGeneratedNever()
                .HasColumnName("idPersonal");
            entity.Property(e => e.Nombre)
                .HasMaxLength(40)
                .HasColumnName("nombre");

            //entity.HasOne(d => d.IdPersonalNavigation).WithOne(p => p.Personalpospro)
            //    .HasForeignKey<Personalpospro>(d => d.IdPersonal)
            //    .OnDelete(DeleteBehavior.ClientSetNull)
            //    .HasConstraintName("FK_personalpospro_usuarios");
        });

        modelBuilder.Entity<Registroclasesemanal>(entity =>
        {
            entity.HasKey(e => e.IdRegistro).HasName("PK__registro__62FC8F5813A4B378");

            entity.ToTable("registroclasesemanal");

            entity.Property(e => e.IdRegistro).HasColumnName("idRegistro");
            entity.Property(e => e.Bruto).HasColumnName("bruto");
            entity.Property(e => e.DuracionBruto)
                .HasPrecision(0)
                .HasColumnName("duracionBruto");
            entity.Property(e => e.Editado).HasColumnName("editado");
            entity.Property(e => e.Editor).HasColumnName("editor");
            entity.Property(e => e.Estado)
                .HasMaxLength(20)
                .HasColumnName("estado");
            entity.Property(e => e.Fecha).HasColumnName("fecha");
            entity.Property(e => e.GrabacionBrutoBorrado).HasColumnName("grabacionBrutoBorrado");
            entity.Property(e => e.Grabado).HasColumnName("grabado");
            entity.Property(e => e.IdHorario).HasColumnName("idHorario");
            entity.Property(e => e.ObservacionesEditorTrimador).HasColumnName("observacionesEditorTrimador");
            entity.Property(e => e.ObservacionesTecnico).HasColumnName("observacionesTecnico");
            entity.Property(e => e.SemanaFin).HasColumnName("semanaFin");
            entity.Property(e => e.SemanaInicio).HasColumnName("semanaInicio");
            entity.Property(e => e.Tecnico).HasColumnName("tecnico");
            entity.Property(e => e.Trimador).HasColumnName("trimador");

            entity.HasOne(d => d.EditorNavigation).WithMany(p => p.RegistroclasesemanalEditorNavigations)
                .HasForeignKey(d => d.Editor)
                .HasConstraintName("FK_Registro_Editor");

            entity.HasOne(d => d.IdHorarioNavigation).WithMany(p => p.Registroclasesemanals)
                .HasForeignKey(d => d.IdHorario)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_Registro_Horario");

            entity.HasOne(d => d.TecnicoNavigation).WithMany(p => p.Registroclasesemanals)
                .HasForeignKey(d => d.Tecnico)
                .HasConstraintName("FK_Registro_Tecnico");

            entity.HasOne(d => d.TrimadorNavigation).WithMany(p => p.RegistroclasesemanalTrimadorNavigations)
                .HasForeignKey(d => d.Trimador)
                .HasConstraintName("FK_Registro_Trimador");
        });

        modelBuilder.Entity<Tecnico>(entity =>
        {
            entity.HasKey(e => e.IdTecnico).HasName("PK__tecnico__295BEDE4CD152B43");

            entity.ToTable("tecnico");

            entity.Property(e => e.IdTecnico)
                .ValueGeneratedNever()
                .HasColumnName("idTecnico");
            entity.Property(e => e.Nombre)
                .HasMaxLength(40)
                .HasColumnName("nombre");

            //entity.HasOne(d => d.IdTecnicoNavigation).WithOne(p => p.Tecnico)
            //    .HasForeignKey<Tecnico>(d => d.IdTecnico)
            //    .OnDelete(DeleteBehavior.ClientSetNull)
            //    .HasConstraintName("FK_Tecnico_Usuarios");
        });

        modelBuilder.Entity<Usuario>(entity =>
        {
            entity.HasKey(e => e.idUsuario).HasName("PK__usuarios__645723A68CC868BC");

            entity.ToTable("usuarios");

            entity.Property(e => e.idUsuario).HasColumnName("idUsuario");
            entity.Property(e => e.email)
                .HasMaxLength(60)
                .HasColumnName("email");
            entity.Property(e => e.estado).HasColumnName("estado");
            entity.Property(e => e.fechaBaja).HasColumnName("fecha_baja");
            entity.Property(e => e.nombre)
                .HasMaxLength(30)
                .HasColumnName("nombre");
            entity.Property(e => e.observaciones)
                .HasMaxLength(100)
                .HasColumnName("observaciones");
            entity.Property(e => e.password)
                .HasMaxLength(200)
                .HasColumnName("password");
            entity.Property(e => e.rol)
                .HasMaxLength(50)
                .HasColumnName("rol");
        });

        OnModelCreatingPartial(modelBuilder);
    }

    partial void OnModelCreatingPartial(ModelBuilder modelBuilder);
}
